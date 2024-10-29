import axios from 'axios';
import { createStore } from 'vuex';

export default createStore({
  state: {
    user: null,
    token: localStorage.getItem('token') || '',
  },
  mutations: {
    setUser(state, user) {
      state.user = user;
    },
    setToken(state, token) {
      state.token = token;
      localStorage.setItem('token', token); // Save token in localStorage
    },
    logout(state) {
      state.user = null;
      state.token = '';
      localStorage.removeItem('token'); // Remove token from localStorage
    },
  },
  actions: {
    async login({ commit }, { Username, Password }) {
      try {
        const response = await axios.post('http://localhost/my-draft2/phpchatbot-jew/login.php', { Username, Password });
        const { token } = response.data; // Destructure the token from response
        commit('setToken', token);
        commit('setUser', { Username }); // Optionally set user info
      } catch (error) {
        console.error('Login failed', error);
        throw error; // Propagate error to the component
      }
    },
    logout({ commit }) {
      commit('logout');
    },
  },
  getters: {
    isAuthenticated: (state) => !!state.token,
    getUser: (state) => state.user,
  },
});
