import CountUp from 'react-countup';

import './styles.scss';

const Counter = function({amount, percent}) {
  return (
    <div className="counter">
      <strong>
        <CountUp end={percent} duration={0.75} />
        <em>%</em>
      </strong>

      <CountUp end={amount} duration={0.75} />
    </div>
  );
}

export default Counter;