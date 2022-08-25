import { useState, useEffect } from 'react';

import QuestionsBlock from '../QuestionsBlock';
import getItems from '../../api/getItems';

import './styles.scss';

const Questions = function({token}) {
  const [items, setItems] = useState([]);

  const [error, setError] = useState(null);
  const [isLoading, setIsLoading] = useState(false);

  useEffect(() => {
    async function fetchData() {
      try {
        const response = await getItems(token);

        if (!response.items) {
          throw new Error();
        }

        setItems(items.concat(response.items));
      } catch (error) {
        if (error.response?.data?.description) {
          setError(error.response?.data?.description);
        }
      } finally {
        setIsLoading(false);
      }
    }

    if (items.length < 10) {
      console.log('refresh');
      fetchData();
    }

    console.log(items);
  }, [token, items]);

  return (
    <div className="questions">
      {isLoading &&
        <p>Загрузка</p>
      }
      {error &&
        <p>{error}</p>
      }
      {items.length &&
        <QuestionsBlock items={items} setItems={setItems} />
      }
    </div>
  );
}

export default Questions;