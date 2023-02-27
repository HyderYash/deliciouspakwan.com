import axios from "axios";

export default async function handler(req, res) {
  const { data } = await axios.get(
    `${process.env.DELICIOUS_URL}/api/shorts/shorts_list.php`
  );
  res.status(200).json(data);
}
