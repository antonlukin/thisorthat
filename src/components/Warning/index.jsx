import './styles.scss';

const Warning = function({children, position, extra}) {
  const classes = ['warning'];

  if (position) {
    classes.push(position);
  }

  return (
    <p className={classes.join(' ')}>
      {extra &&
        <strong>Произошла ошибка</strong>
      }

      <span>{children}</span>

      {extra &&
        <span>Попробуйте обновить страницу.</span>
      }
    </p>
  );
}

export default Warning;