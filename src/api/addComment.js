import axios from 'axios';

const addComment = async (token, item, message) => {
  const data = new FormData();
  data.append('token', token);
  data.append('item_id', item);
  data.append('message', message);

  const response = await axios('/addComment', {
    method: 'POST',
    data: data,
  });

  return response.data.result;
}

export default addComment;