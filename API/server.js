const express = require('express');
const cors = require('cors');
const db = require('./db');
const app = express();
const port = 3000;

app.use(cors()); // Enable CORS for all routes
app.use(express.json()); // Middleware to parse JSON bodies

app.get('/', (req, res) => {
  res.send('Hello, World!');
});

app.get('/api/data', async (req, res) => {
    try {
        const [rows] = await db.query('SELECT * FROM stretcher_register');
      res.json(rows);
    } catch (err) {
      res.status(500).json({ error: err.message });
    }
});

app.get('/api/timestamp', async (req, res) => {
    try {
        const [rows] = await db.query('SELECT ผู้รับ, stretcher_register_send_time, stretcher_register_return_time, stretcher_register_accept_date FROM stretcher_register');
      res.json(rows);
    } catch (err) {
      res.status(500).json({ error: err.message });
    }
});

app.get('/api/people', async (req, res) => {
  try {
      const [rows] = await db.query('SELECT stretcher_register_accept_date, from_depcode, send_depcode, stretcher_work_status_id, stretcher_register_accept_date, ผู้รับ FROM stretcher_register');
    res.json(rows);
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

app.get('/api/home', async (req, res) => {
  try {
      const [rows] = await db.query('SELECT ผู้รับ, from_depcode, send_depcode, stretcher_work_status_id, stretcher_register_accept_date FROM stretcher_register');
    res.json(rows);
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

app.get('/api/users', async (req, res) => {
  try {
      const [rows] = await db.query('SELECT * FROM users');
    res.json(rows);
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

app.post('/api/addusers', async (req, res) => {
  const { ไอดี, ชื่อ, ตำแหน่ง } = req.body;

  try {
    // Check if user with the same ID already exists
    const [existingUser] = await db.query('SELECT * FROM users WHERE ไอดี = ?', [ไอดี]);
    if (existingUser.length > 0) {
      return res.status(400).json({ error: 'User with this ID already exists' });
    }

    // Insert new user into the database
    await db.query('INSERT INTO users (ชื่อ, ตำแหน่ง) VALUES (?, ?)', [ชื่อ, ตำแหน่ง]);

    res.status(201).json({ message: 'User added successfully' });
  } catch (err) {
    res.status(500).json({ error: err });
  }
});

app.listen(port, () => {
  console.log(`Server is running on http://localhost:${port}`);
});