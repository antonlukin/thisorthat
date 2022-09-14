import axios from 'axios';

const getComments = async (token, item) => {
  const data = new FormData();
  data.append('token', token);
  data.append('item_id', item);
  data.append('limit', 100);

  const response = await axios('https://api.thisorthat.ru/getComments', {
    method: 'POST',
    data: data,
  });

  return response.data.result?.comments;
}

export default getComments;