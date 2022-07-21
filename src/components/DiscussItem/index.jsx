import './styles.scss';

const DiscussItem = function({comment}) {
  return (
    <div className="discuss-item">
        <img src={comment.avatar} alt={comment.name} />
        <span>{comment.name}</span>
        <p>{comment.message}</p>
    </div>
  );
}

export default DiscussItem;