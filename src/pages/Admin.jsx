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

  const token = useContext(GameContext);

  useEffect(() => {
    setLoader(true);

    async function getItems() {
      try {
        const data = await API.getAudit(token);
        setItems(items.concat(data));
      } catch (error) {
        setWarning('Не удалось загрузить вопросы. Попробуйте обновить страницу.');
      }
    }

    if (items.length > 0) {
      setLoader(false);
    }

    if (items.length < 5) {
      getItems();
    }
  }, [token, items, setLoader, setWarning]);

  function removeItem(id) {
    setItems(items.filter(object => object.item_id !== id));
  }

  return (
    <>
      {items.length > 0 &&
        <Page>
          <Backlink>Модерация вопросов</Backlink>

          <Content>
            <p>
              В этом разделе отображаются новые вопросы от пользователей.
              Вы можете принять участие в модерации.
              Голосуйте за понравившиеся вопросы, и они появятся в основном разделе.
            </p>
          </Content>

          <Moderation>
            {items && items.slice(0, 8).map((item) =>
              <ModerationItem item={item} removeItem={removeItem} key={item.item_id} />
            )}
          </Moderation>
        </Page>
      }
    </>
  );
}

export default Admin;