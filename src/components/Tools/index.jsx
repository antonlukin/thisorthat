import './styles.scss';

const Tools = function({loadComments}) {
  return (
    <div className="tools">
      <button className="like">Нравится</button>
      <button className="replies" onClick={loadComments}>Комментарии</button>
      <button className="dislike">Не нравится</button>
    </div>
  );
}

export default Tools;