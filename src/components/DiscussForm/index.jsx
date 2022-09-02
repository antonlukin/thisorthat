import { useState, useContext } from 'react';
import TextareaAutosize from 'react-textarea-autosize';

import API from '../../api';
import AuthContext from '../../context';

import Loader from '../Loader';
import Warning from '../Warning';

import './styles.scss';

const DiscussForm = function({current, addComment}) {
  const [message, setMessage] = useState('');
  const [warning, setWarning] = useState('');
  const [isLoading, setIsLoading] = useState(false);

  const token = useContext(AuthContext);

  async function submitForm(e) {
    e.preventDefault();

    setIsLoading(true);

    try {
      const data = await API.addComment(token, current.item_id, message);
      addComment(data);

      setMessage('');
    } catch (error) {
      setWarning(error.response.data?.description || 'Не удалось добавить комментарий');
    }

    setIsLoading(false);
  }

  function updateMessage(e) {
    e.preventDefault();

    if (!isLoading) {
      setMessage(e.target.value);
    }

    setWarning('');
  }

  return (
    <form className="discuss-form" action="/" onSubmit={submitForm}>
      <fieldset>
        <TextareaAutosize
          type="text"
          value={message}
          rows="1"
          maxLength={300}
          onChange={updateMessage}
          placeholder="Ваш комментарий"
        />

        {isLoading
          ? <Loader position="on-discuss" />
          : message && <button type="submit">Отправить</button>
        }
      </fieldset>

      {warning &&
        <Warning position="on-discuss">{warning}</Warning>
      }
    </form>
  );
}

export default DiscussForm;