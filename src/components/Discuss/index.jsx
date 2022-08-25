import DiscussItem from '../DiscussItem';
import DiscussForm from '../DiscussForm';

import './styles.scss';

const Discuss = function({discussRef}) {
  const comments = [{"comment_id":"6","user_id":"15","parent":"0","message":"Конечно, музыку. Я интроверт","name":"Политический купец","avatar":"https://image.thisorthat.ru/100/15"},{"comment_id":"7","user_id":"16","parent":"0","message":"Я за первый вариант. Люди умного и не говорят","name":"Исключительный корпус","avatar":"https://image.thisorthat.ru/100/16"},{"comment_id":"8","user_id":"17","parent":"0","message":"Лучше в компании музыку слушать, чем одному","name":"Колоссальный обжиг","avatar":"https://image.thisorthat.ru/100/17"},{"comment_id":"9","user_id":"19","parent":"0","message":"Мне веселее с людьми общаться","name":"Руководящий психоз","avatar":"https://image.thisorthat.ru/100/19"},{"comment_id":"10","user_id":"21","parent":"0","message":"Очень сложный вопрос","name":"Непривычный купорос","avatar":"https://image.thisorthat.ru/100/21"},{"comment_id":"8424","user_id":"1054574","parent":"0","message":"конечно музыку","name":"Деловой палех","avatar":"https://image.thisorthat.ru/100/1054574"},{"comment_id":"21388","user_id":"1187757","parent":"0","message":"Лучше вообще слушать тишину","name":"Предыдущий сплин","avatar":"https://image.thisorthat.ru/100/1187757"}]

  return (
    <div className="discuss" ref={discussRef}>
      {comments.map((comment) =>
        <DiscussItem comment={comment} key={comment.comment_id} />
      )}

      <DiscussForm />
    </div>
  );
}

export default Discuss;