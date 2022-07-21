import './styles.scss';

const Tools = function() {
  return (
    <div className="tools">
      <button className="like">Нравится</button>
      <button className="replies">Комментарии</button>
      <button className="dislike">Не нравится</button>
    </div>
  );
}

export default Tools;