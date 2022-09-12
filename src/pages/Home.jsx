import { useState, useEffect, useContext } from 'react';

import API from '../api';
import AuthContext from '../context';

import Page from '../components/Page';
import Header from '../components/Header';
import Questions from '../components/Questions';
import Tools from '../components/Tools';
import Discuss from '../components/Discuss';
import Menu from '../components/Menu';

const Home = function({setWarning, setLoader}) {
  const [items, setItems] = useState([]);
  const [current, setCurrent] = useState(null);
  const [discussed, setDiscussed] = useState(false);

  const token = useContext(AuthContext);

  useEffect(() => {
    setLoader(true);

    async function getItems() {
      try {
        const data = await API.getItems(token);
        setItems(items.concat(data));
      } catch (error) {
        setWarning('Не удалось загрузить вопросы.');
      }
    }

    if (items.length > 0) {
      setLoader(false);
      setCurrent(items[0]);
    }

    if (token && items.length < 5) {
      getItems();
    }
  }, [token, items, setLoader, setWarning]);

  function toggleComments() {
    if (!discussed) {
      return setDiscussed(true);
    }

    document.body.scrollIntoView({ behavior: 'smooth' });

    setTimeout(() => {
      setDiscussed(false);
    }, 600);
  }

  function updateItems(callback) {
    setItems(items.slice(1));

    return callback();
  }

  function shiftItem(callback) {
    if (!discussed) {
      return updateItems(callback);
    }

    document.body.scrollIntoView({ behavior: 'smooth' });

    setTimeout(() => {
      setDiscussed(false);
      updateItems(callback);
    }, 600);
  }

  return (
    <>
      {current &&
        <>
          <Menu />
          <Page>
            <Header />
            <Questions current={current} shiftItem={shiftItem} />
            <Tools current={current} toggleComments={toggleComments} />

            {discussed &&
              <Discuss current={current} />
            }
          </Page>
        </>
      }
    </>
  );
}

export default Home;