import { useState } from 'react';

import Versus from '../Versus';
import Counter from '../Counter';

import './styles.scss';

const Questions = function() {
  const [result, setResult] = useState(null);

  const item = {
    "item_id": "5811",
    // "first_text": "Дневники Вампира",
    "first_text": "Конечно, музыку. Я интроверт. Конечно, музыку. Я интроверт. Конечно, музыку. Я интроверт. Конечно, музыку. Я интроверт. Конечно, музыку. Я интроверт 2",
    "last_text": "Сверхъестественное",
    "status": "approved",
    "first_vote": 4267,
    "last_vote": 10982
  };

  const first = {
    id: item.item_id,
    text: item.first_text,
    amount: item.first_vote,
    percent: Math.ceil(item.first_vote / item.last_vote * 100),
  };

  const last = {
    id: item.item_id,
    text: item.last_text,
    amount: item.last_vote,
    percent: 100 - first.percent,
  };

  return (
    <div className="questions">
      <div className="questions-item" onClick={(e) => setResult(first)}>
        {result &&
          <Counter result={first} />
        }

        <p>{first.text}</p>
      </div>

      <Versus />

      <div className="questions-item" onClick={(e) => setResult(last)}>
        {result &&
          <Counter result={last} />
        }

        <p>{last.text}</p>
      </div>
    </div>
  );
}

export default Questions;