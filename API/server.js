const express = require('express');
const cors = require('cors');
const bcrypt = require('bcrypt');
const jwt = require('jsonwebtoken');
const db = require('./db');
const app = express();
const port = 3000;

const jwtSecret = 'your_jwt_secret';

app.use(cors()); // Enable CORS for all routes
app.use(express.json()); // Middleware to parse JSON bodies

//Middleware
// const checkSignIn = (req, res, next) => {
//   try {
//     const token = req.headers["authorization"];

//     const key = jwtSecret;
//     jwt.verify(token, key);

//     next();
//   } catch (error) {
//     return res.status(500).send({ error: error.message });
//   }
// };

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
    const [rows] = await db.query('SELECT stretcher_register_accept_date, from_depcode, send_depcode, stretcher_work_status_id, stretcher_register_send_time, stretcher_register_return_time, ผู้รับ FROM stretcher_register');
    res.json(rows);
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

// app.get('/api/home', async (req, res) => {
//   try {
//       const [rows] = await db.query('SELECT ผู้รับ, from_depcode, send_depcode, stretcher_work_status_id, stretcher_register_accept_date FROM stretcher_register');
//     res.json(rows);
//   } catch (err) {
//     res.status(500).json({ error: err.message });
//   }
// });

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

//Register
app.post('/api/register/', async (req, res) => {
  const { username, email, password, name } = req.body;
  try {
    // Validate input data
    if (!username || !email || !password || !name) {
      return res.status(400).json({ error: 'All fields are required' });
    }

    // Hash the password before saving
    const hashedPassword = await bcrypt.hash(password, 10);

    // Insert new user into the database
    const [rows] = await db.query(
      `INSERT INTO users (ชื่อ, Email, Password, Username) VALUES (?, ?, ?, ?)`,
      [username, email, hashedPassword, name]
    );

    // Return success message
    res.status(201).json({ message: 'User registered successfully', userId: rows.insertId });
  } catch (error) {
    console.error(error);
    res.status(500).json({ error: 'An error occurred during registration' });
  }
});

// Sign-In
app.post('/api/signIn', async (req, res) => {
  const { username, password } = req.body;

  try {
    // Validate input data
    if (!username || !password) {
      return res.status(400).json({ error: 'All fields are required' });
    }

    // Check if user exists in the database
    const [rows] = await db.query(`SELECT * FROM users WHERE Username = ?`, [username]);

    if (rows.length === 0) {
      return res.status(404).json({ error: 'User not found' });
    }

    const user = rows[0];

    // Compare the password with the hashed password stored in the database
    const isPasswordValid = await bcrypt.compare(password, user.Password);

    if (!isPasswordValid) {
      return res.status(401).json({ error: 'Invalid password' });
    }

    // (Optional) Generate a JWT for session handling
    const token = jwt.sign({ userId: user.id, username: user.Username }, jwtSecret, {
      expiresIn: '1h', // Token expires in 1 hour
    });

    // Return success message along with user data (or token)
    res.status(200).json({ message: 'Sign-in successful', token });
  } catch (error) {
    console.error(error);
    res.status(500).json({ error: 'An error occurred during sign-in' });
  }
});


app.listen(port, () => {
  console.log(`Server is running on http://localhost:${port}`);
});
//