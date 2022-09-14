import { useState, useContext } from 'react';
import TextareaAutosize from 'react-textarea-autosize';

import { ReactComponent as SendIcon } from '../../images/send.svg';

import API from '../../api';
import GameContext from '../../context';

import Loader from '../Loader';
import Warning from '../Warning';

import './styles.scss';

const DiscussForm = function({current, addComment}) {
  const [message, setMessage] = useState('');
  const [warning, setWarning] = useState('');
  const [loading, setLoading] = useState(false);

  const token = useContext(GameContext);

  async function submitForm(e) {
    e.preventDefault();

    setWarning('');
    setLoading(true);

    try {
      addComment(await API.addComment(token, current.item_id, message));
      setMessage('');
    } catch (error) {
      setWarning(error.response.data?.description || 'Не удалось добавить комментарий');
    }

    setLoading(false);
  }

  function updateMessage(e) {
    e.preventDefault();

    if (!loading) {
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

        {loading
          ? <Loader position="on-discuss" />
          : message && <button type="submit"><SendIcon /></button>
        }
      </fieldset>

      {warning &&
        <Warning position="on-discuss">{warning}</Warning>
      }
    </form>
  );
}

export default DiscussForm;