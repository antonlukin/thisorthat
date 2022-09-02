import { useState, useEffect, useContext } from 'react';

import API from '../../api';
import AuthContext from '../../context';

import ModerationItem from '../ModerationItem';

import './styles.scss';

const Moderation = function() {
  const [items, setItems] = useState([]);
  const token = useContext(AuthContext);

  useEffect(() => {
    async function getItems() {
      try {
        const data = await API.getRecent(token);
        setItems(data);
      } catch(error) {
        setItems([]);
      }
    }

    getItems();
  }, [token]);

  return (
    <div className="moderation">
      {items && items.map((item) =>
        <ModerationItem item={item} key={item.item_id} />
      )}
    </div>
  );
}

export default Moderation;