import './styles.scss';

const Loader = function({position}) {
  const classes = ['loader'];

  if (position) {
    classes.push(position);
  }

  return (
    <div className={classes.join(' ')}></div>
  );
}

export default Loader;