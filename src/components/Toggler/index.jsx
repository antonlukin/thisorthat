import './styles.scss';

const Toggler = function({opened, setOpened}) {
  const classes = ['toggler'];

  if (opened) {
    classes.push('is-opened');
  }

  function toggleMenu(e) {
    e.preventDefault();

    if (opened) {
      return setOpened(false);
    }

    setOpened(true);
  }

  return (
    <button type="button" className={classes.join(' ')} onClick={toggleMenu}>
      <span></span>
      <span></span>
      <span></span>
    </button>
  );
}

export default Toggler;