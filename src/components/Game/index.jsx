import { useState, useEffect } from 'react';

import Header from '../Header';
import Background from '../Background'
import Questions from '../Questions';
import Tools from '../Tools';
// import Discuss from '../Discuss';

import register from '../../api/register';

import './styles.scss';

const Game = function() {
  const [token, setToken] = useState(null);

  document.documentElement.style.setProperty('--app-height', `${window.innerHeight}px`);

  useEffect(() => {
    async function fetchData() {
      try {
        const response = await register(token);

        if (!response.token) {
          throw new Error();
        }

        setToken(response.token);
        localStorage.setItem('token', response.token);
      } catch (error) {
        console.log(error);
      }
    }

    const localToken = localStorage.getItem('token');

    if (localToken === null) {
      fetchData();
    } else {
      setToken(localToken);
    }
  }, [token]);

  return (
    <>
      {token &&
        <div className="game">
          <Header />
          <Questions token={token} />
          <Tools />

          {/* <Discuss /> */}
        </div>
      }

      <Background />
    </>
  );
}

export default Game;