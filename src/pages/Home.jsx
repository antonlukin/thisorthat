import { useState, useEffect, useContext } from 'react';
import smoothScroll from '../utils/scroller';

import API from '../api';
import GameContext from '../context';

import Page from '../components/Page';
import Header from '../components/Header';
import Questions from '../components/Questions';
import Tools from '../components/Tools';
import Discuss from '../components/Discuss';
import Menu from '../components/Menu';


const Home = function({setWarning, setLoader}) {
  const [current, setCurrent] = useState(null);
  const [discussed, setDiscussed] = useState(false);
  const [items, setItems] = useState([]);

  const token = useContext(GameContext);

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

    if (items.length < 5 && token) {
      getItems();
    }
  }, [token, items, setLoader, setWarning]);

  function toggleComments() {
    if (!discussed) {
      return setDiscussed(true);
    }

    smoothScroll(document.body, () => {
      setDiscussed(false);
    });
  }

  function updateItems(callback) {
    setItems(items.slice(1));

    return callback();
  }

  function shiftItem(callback) {
    if (!discussed) {
      return updateItems(callback);
    }

    smoothScroll(document.body, () => {
      setDiscussed(false);
      updateItems(callback)
    });
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