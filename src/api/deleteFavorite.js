import axios from 'axios';

const deleteFavorite = async (token, item) => {
  const data = new FormData();
  data.append('token', token);
  data.append('item_id', item);

  const response = await axios('https://api.thisorthat.ru/deleteFavorite', {
    method: 'POST',
    data: data,
  });

  return response.data.result;
}

export default deleteFavorite;