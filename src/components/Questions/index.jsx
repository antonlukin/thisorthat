import { useState, useEffect, useContext } from 'react';

import API from '../../api';
import GameContext from '../../context';

import Versus from '../Versus';
import QuestionsItem from '../QuestionsItem';

import './styles.scss';

const Questions = function({current, shiftItem}) {
  const [result, setResult] = useState(null);
  const [delay, setDelay] = useState(0);

  const token = useContext(GameContext);
  const prepared = prepareItem(current);

  useEffect(() => {
    async function setViewed() {
      try {
        await API.setViewed(token, current.item_id, result);
      } catch (error) {
        console.error(error);
      }
    }

    if (result) {
      setViewed();
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
      setDelay(Math.round(window.performance.now()));

      return setResult(pick);
    }

    // Wait until counters are displayed
    if (delay + 1000 > Math.round(window.performance.now())) {
      return;
    }

    shiftItem(() => {
      setResult(null);
    });
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