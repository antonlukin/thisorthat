import { useEffect } from 'react';
import CountUp from 'react-countup';

import './styles.scss';

const Counter = function({result}) {
  useEffect(() => {
    console.log(result);
  });

  return (
    <div className="counter">
      <strong>
        <CountUp end={result.percent} duration={0.75} />
        <em>%</em>
      </strong>

      <CountUp end={result.amount} duration={0.75} />
    </div>
  );
}

export default Counter;