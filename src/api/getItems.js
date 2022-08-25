import axios from 'axios';

const getItems = async (token) => {
  const data = new FormData();
  data.append('token', token);

  const response = await axios('/getItems', {
    method: 'POST',
    data: data,
  });

  return response.data.result;
}

export default getItems;