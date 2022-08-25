import { useState, useEffect } from 'react';

import Versus from '../Versus';
import QuestionsItem from '../QuestionsItem';

const QuestionsBlock = function({items, setItems}) {
  const [result, setResult] = useState(null);

  const start = Math.round(window.performance.now());
  const item = prepareItem(items[0]);

  useEffect(() => {
    if (result) {
      console.log('send result');
    }
  }, [result]);

  function prepareItem(current) {
    const item = {};
    const sum = current.first_vote + current.last_vote;

    item.first = {
      pick: 'first',
      text: current.first_text,
      amount: current.first_vote,
      percent: Math.ceil(current.first_vote / sum  * 100),
    };

    item.last = {
      pick: 'last',
      text: current.last_text,
      percent: 100 - item.first.percent,
      amount: current.last_vote
    };

    return item;
  }

  function updateResult(pick) {
    if (result === null) {
      return setResult(pick);
    }

    const end = Math.round(window.performance.now());

    // Wait until counters are displayed
    if (start > end - 1000) {
      return;
    }

    setResult(null);
    setItems(items.slice(1));
  }

  return (
    <>
      <QuestionsItem data={item.first} result={result} updateResult={updateResult} />
      <Versus />
      <QuestionsItem data={item.last} result={result} updateResult={updateResult} />
    </>
  );
}

export default QuestionsBlock;