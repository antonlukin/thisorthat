import React from 'react';
import ReactDOM from 'react-dom/client';

import { useState, useEffect } from 'react';
import { BrowserRouter, Routes, Route } from 'react-router-dom';

import API from './api';
import GameContext from './context';

import Home from './pages/Home';
import About from './pages/About';
import Admin from './pages/Admin';

import Loader from './components/Loader'
import Warning from './components/Warning';
import Logout from './components/Logout';
import Background from './components/Background';

import './styles/fonts.scss';
import './styles/colors.scss';
import './styles/animations.scss';

import './index.scss';

const App = function() {
  document.documentElement.style.setProperty('--app-height', `${window.innerHeight}px`);

  const [warning, setWarning] = useState(null);
  const [loader, setLoader] = useState(true);
  const [token, setToken] = useState(null);

  useEffect(() => {
    async function getToken() {
      try {
        const data = await API.register();
        setToken(data);

        localStorage.setItem('token', data);
      } catch(error) {
        setWarning('Не удалось зарегистрироваться.');
      }
    }

    const localToken = localStorage.getItem('token');
    setToken(localToken);

    if (localToken === null) {
      getToken();
    }
  }, []);

  return (
    <>
      {warning &&
        <>
          <Logout />
          <Warning position="on-welcome" extra={true}>{warning}</Warning>
        </>
      }
      {!warning && loader &&
        <Loader />
      }
      {!warning && token &&
        <GameContext.Provider value={token}>
          <BrowserRouter>
            <Routes>
              <Route path="/" element={<Home setLoader={setLoader} setWarning={setWarning} />} />
              <Route path="/about" element={<About setLoader={setLoader} />} />
              <Route path="/admin" element={<Admin setLoader={setLoader} setWarning={setWarning} />} />
            </Routes>
          </BrowserRouter>
        </GameContext.Provider>
      }
      <Background />
    </>
  );
}

const root = ReactDOM.createRoot(document.getElementById('root'));
root.render(
  <App />
);