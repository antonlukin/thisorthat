import React from 'react';
import ReactDOM from 'react-dom/client';

import { BrowserRouter, Routes, Route } from 'react-router-dom';

import Home from './pages/Home';
import About from './pages/About';
import Admin from './pages/Admin';

import './styles/fonts.scss';
import './styles/colors.scss';
import './styles/animations.scss';

import './index.scss';

document.documentElement.style.setProperty('--app-height', `${window.innerHeight}px`);

const root = ReactDOM.createRoot(document.getElementById('root'));
root.render(
  <BrowserRouter>
    <Routes>
      <Route path="/" element={<Home />} />
      <Route path="/about" element={<About />} />
      <Route path="/admin" element={<Admin />} />
    </Routes>
  </BrowserRouter>
);