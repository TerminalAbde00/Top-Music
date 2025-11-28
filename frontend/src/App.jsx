//import React from 'react';
import { BrowserRouter as Router, Route, Routes } from 'react-router-dom';
import './App.css';
import MainPage from './pages/MainPage';
import PlayerPage from './pages/PlayerPage';
import LoginPage from './pages/LoginPage';


const App = () => {
  return (
    <div>
      <Router>
        <Routes>
          <Route path="/" element={<MainPage />} />
          <Route path="/player/:id" element={<PlayerPage />} />
          <Route path="/LoginPage" element={<LoginPage />} />          
        </Routes>
      </Router>
    </div>
  );
};

export default App;