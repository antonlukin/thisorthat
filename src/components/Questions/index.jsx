import { useState, useEffect, useContext } from 'react';

import API from '../../api';
import AuthContext from '../../context';

import Versus from '../Versus';
import QuestionsItem from '../QuestionsItem';

import './styles.scss';

const Questions = function({current, shiftItem}) {
  const [result, setResult] = useState(null);
  const token = useContext(AuthContext);

  const start = Math.round(window.performance.now());
  const prepared = prepareItem(current);

  useEffect(() => {
    async function sendData() {
      try {
        await API.setViewed(token, current.item_id, result);
      } catch (error) {
        console.error(error);
      }
    }

    if (result) {
      console.log('send data');
      sendData();
    }
  }, [result, current, token]);

  function prepareItem(current) {
    const prepared = {};
    const sum = current.first_vote + current.last_vote;

    prepared.first = {
      pick: 'first',
      text: current.first_text,
      amount: current.first_vote,
      percent: Math.ceil(current.first_vote / sum  * 100),
    };

    prepared.last = {
      pick: 'last',
      text: current.last_text,
      percent: 100 - prepared.first.percent,
      amount: current.last_vote
    };

    return prepared;
  }

  function updateResult(pick) {
    if (!result) {
      return setResult(pick);
    }

    const end = Math.round(window.performance.now());

    // Wait until counters are displayed
    if (start > end - 1000) {
      return;
    }

    setResult(null);
    shiftItem();
  }

  return (
    <div className="questions">
      <QuestionsItem data={prepared.first} result={result} updateResult={updateResult} />
      <Versus />
      <QuestionsItem data={prepared.last} result={result} updateResult={updateResult} />
    </div>
  );
}

export default Questions;