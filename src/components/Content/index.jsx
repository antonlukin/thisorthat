import './styles.scss';

const Content = function({children}) {
  return (
    <div className="content">
      {children}
    </div>
  );
}

export default Content;