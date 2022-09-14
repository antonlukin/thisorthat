import axios from 'axios';

const getItems = async (token) => {
  const data = new FormData();
  data.append('token', token);
  data.append('status', 'approved');

  const response = await axios('https://api.thisorthat.ru/getItems', {
    method: 'POST',
    data: data,
  });

  return response.data.result?.items;
}

export default getItems;