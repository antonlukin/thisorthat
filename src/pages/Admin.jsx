import { useState, useEffect, useContext } from 'react';

import API from '../api';
import AuthContext from '../context';

import Page from '../components/Page';
import Content from '../components/Content';
import Backlink from '../components/Backlink';
import ModerationItem from '../components/ModerationItem';

const Admin = function({setWarning, setLoader}) {
  const [items, setItems] = useState([]);
  const token = useContext(AuthContext);

  useEffect(() => {
    setLoader(true);

    async function getItems() {
      try {
        const data = await API.getRecent(token);
        setItems(items.concat(data));
      } catch (error) {
        setWarning('Не удалось загрузить вопросы. Попробуйте обновить страницу.');
      }
    }

    if (items.length) {
      setLoader(false);
    }

    if (items.length < 5) {
      getItems();
    }
  }, [token, items, setLoader, setWarning]);

  return (
    <>
      {items.length > 0 &&
        <Page>
          <Backlink>Модерация вопросов</Backlink>

          <Content>
            <p>
              В этом разделе отображаются новые вопросы от пользоваталей.
              Вы можете принять участие в модерации.
              Голосуйте за понравившиеся вопросы, и они появятся в основном разделе.
            </p>
          </Content>

          <div className="moderation">
            {items && items.slice(0, 8).map((item) =>
              <ModerationItem item={item} key={item.item_id} />
            )}
          </div>
        </Page>
      }
    </>
  );
}

export default Admin;