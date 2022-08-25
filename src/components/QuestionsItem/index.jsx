import Counter from '../Counter';

import './styles.scss';

const QuestionsItem = function({data, result, updateResult}) {
  const classes = ['questions-item'];

  if (result === null) {
    classes.push('is-recent');
  }

  if (data.pick === result) {
    classes.push('is-picked');
  }

  return (
    <div className={classes.join(' ')} onClick={(e) => updateResult(data.pick)}>
      <p>{data.text}</p>

      {result &&
        <Counter amount={data.amount} percent={data.percent} />
      }
    </div>
  );
}

export default QuestionsItem;