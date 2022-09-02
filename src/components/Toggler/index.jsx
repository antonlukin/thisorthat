import './styles.scss';

const Toggler = function({isOpened, setIsOpened}) {
  const classes = ['toggler'];

  if (isOpened) {
    classes.push('is-opened');
  }

  function toggleMenu(e) {
    e.preventDefault();

    if (isOpened) {
      return setIsOpened(false);
    }

    setIsOpened(true);
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