import axios from 'axios';

const getComments = async (token, item) => {
  const data = new FormData();
  data.append('token', token);
  data.append('item_id', item);

  const response = await axios('/getComments', {
    method: 'POST',
    data: data,
  });

  return response.data.result?.comments;
}

export default getComments;