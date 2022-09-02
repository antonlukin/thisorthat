import './styles.scss';

const Warning = function({children, position}) {
  const classes = ['warning'];

  if (position) {
    classes.push(position);
  }

  return (
    <p className={classes.join(' ')}>
      <strong>Произошла ошибка</strong>
      <span>{children}</span>
    </p>
  );
}

export default Warning;