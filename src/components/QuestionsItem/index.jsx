import Counter from '../Counter';

import './styles.scss';

const QuestionsItem = function({data, result, updateResult}) {
  const classes = ['questions-item'];

  if (!result) {
    classes.push('is-recent');
  }

  if (data.pick === result) {
    classes.push('is-picked');
  }

  function selectItem() {
    updateResult(data.pick)
  }

  return (
    <div className={classes.join(' ')} onClick={selectItem}>
      <p>{data.text}</p>

      {result &&
        <Counter amount={data.amount} percent={data.percent} />
      }
    </div>
  );
}

export default QuestionsItem;