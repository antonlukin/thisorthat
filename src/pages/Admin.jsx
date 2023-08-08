import { useState, useEffect, useContext } from 'react';

import API from '../api';
import GameContext from '../context';

import Page from '../components/Page';
import Content from '../components/Content';
import Backlink from '../components/Backlink';
import Moderation from '../components/Moderation';
import ModerationItem from '../components/ModerationItem';

const Admin = function({setWarning, setLoader}) {
  const [items, setItems] = useState([]);
  const [storage, setStorage] = useState({});
  const [watched, setWatched] = useState(false);

  const token = useContext(GameContext);

  useEffect(() => {
    setLoader(true);

    async function getItems() {
      const updated = storage;

      try {
        const data = await API.getAudit(token);

        data.forEach(item => {
          if (!updated[item.item_id]) {
            updated[item.item_id] = item;
          }
        });

        if (data.length < 10) {
          setWatched(true);
        }

        setStorage(updated);
        setItems(Object.keys(updated).map((key) => updated[key]));
      } catch (error) {
        setWarning('Не удалось загрузить вопросы. Попробуйте обновить страницу.');
      }
    }

    if (watched || items.length > 0) {
      setLoader(false);
    }

    if (!watched && items.length < 10) {
      getItems();
    }
  }, [token, items, storage, watched, setLoader, setWarning]);

  function removeItem(id) {
    const updated = storage;
    delete updated[id];

    setStorage(updated);
    setItems(Object.keys(updated).map((key) => updated[key]));
  }

  return (
    <>
      <Page>
        <Backlink>Модерация вопросов</Backlink>

        <Content>
          <p>
            В этом разделе отображаются новые вопросы от пользователей.
            Вы можете принять участие в модерации.
            Голосуйте за понравившиеся вопросы, и они появятся в основном разделе.
          </p>
        </Content>

        {items.length > 0 &&
          <Moderation>
            {items && items.map((item) =>
              <ModerationItem item={item} removeItem={removeItem} key={item.item_id} />
            )}
          </Moderation>
        }

        {items.length === 0 && watched &&
          <p>
            <strong>Вы промодерировали все вопросы.</strong>
          </p>
        }
      </Page>
    </>
  );
}

export default Admin;