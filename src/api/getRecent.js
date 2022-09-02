import axios from 'axios';

const getRecent = async (token) => {
  const data = new FormData();
  data.append('token', token);
  data.append('status', 'new');

  const response = await axios('/getItems', {
    method: 'POST',
    data: data,
  });

  return response.data.result?.items;
}

export default getRecent;