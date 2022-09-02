import { useState, useEffect } from 'react';

import API from '../../api';
import AuthContext from '../../context';

import Header from '../Header';
import Questions from '../Questions';
import Tools from '../Tools';
import Discuss from '../Discuss';
import Loader from '../Loader';
import Warning from '../Warning';

const Game = function() {
  const [token, setToken] = useState(null);
  const [items, setItems] = useState([]);
  const [current, setCurrent] = useState(null);
  const [warning, setWarning] = useState(null);

  const [isLoading, setIsLoading] = useState(true);
  const [isDiscuss, setIsDiscuss] = useState(false);

  useEffect(() => {
    async function getToken() {
      try {
        const data = await API.register();
        setToken(data);

        localStorage.setItem('token', data);
      } catch(error) {
        setWarning('Не удалось зарегистрироваться. Попробуйте обновить страницу');
      }
    }

    const localToken = localStorage.getItem('token');
    setToken(localToken);

    if (!localToken) {
      getToken();
    }
  }, [token]);

  useEffect(() => {
    async function getItems() {
      try {
        const data = await API.getItems(token);
        setItems(items.concat(data));
      } catch (error) {
        setWarning('Не удалось загрузить вопросы. Попробуйте обновить страницу.');
      }
    }

    if (items.length) {
      setIsLoading(false);
      setCurrent(items[0]);
    }

    if (token && items.length < 5) {
      getItems();
    }
  }, [token, items]);

  function toggleComments() {
    if (!isDiscuss) {
      return setIsDiscuss(true);
    }

    document.body.scrollIntoView({ behavior: "smooth" });

    setTimeout(() => {
      setIsDiscuss(false);
    }, 600);
  }

  function shiftItem() {
    if (!isDiscuss) {
      return setItems(items.slice(1));
    }

    toggleComments();
    setItems(items.slice(1));
  }

  return (
    <>
      {warning &&
        <Warning>{warning}</Warning>
      }
      {!warning && isLoading &&
        <Loader />
      }
      {!warning && current &&
        <AuthContext.Provider value={token}>
          <Header />
          <Questions current={current} shiftItem={shiftItem} />
          <Tools current={current} toggleComments={toggleComments} />

          {isDiscuss &&
            <Discuss current={current} />
          }
        </AuthContext.Provider>
      }
    </>
  );
}

export default Game;