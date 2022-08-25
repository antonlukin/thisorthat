import { useState, useEffect } from 'react';

import Header from '../Header';
import Background from '../Background'
import Questions from '../Questions';
import Tools from '../Tools';
// import Discuss from '../Discuss';

import './styles.scss';

const Game = function() {
  const [token, setToken] = useState(null);

  document.documentElement.style.setProperty('--app-height', `${window.innerHeight}px`);

  useEffect(() => {
    const fetchData = async () => {
      let url = '/register/';

      const data = new FormData();
      data.append('client', 'test');
      data.append('uniqid', '0');

      const options = {
        method: 'POST',
        body: data
      };

      const response = await fetch(url, options);

      if (response.status > 200) {
          return console.log('123');
      }

      const answer = await response.json();

      setToken(answer.result.token);
      localStorage.setItem('token', answer.result.token);
    };

    const localToken = localStorage.getItem('token');

    if (localToken === null) {
      fetchData();
    } else {
      setToken(localToken);
    }
  }, []);

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