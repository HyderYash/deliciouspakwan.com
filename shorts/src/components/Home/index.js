import axios from "axios";
import React, { useEffect, useState } from "react";
import { useRouter } from "next/router";

const Home = () => {
  const [allShorts, setAllShorts] = useState([]);
  const router = useRouter();
  const getAllShorts = async () => {
    const ytShortsURL = `https://www.googleapis.com/youtube/v3/search?videoDuration=short&channelId=UCg22-16kmYWZTUQF9wVkqFA&order=date&part=snippet&type=video&maxResults=1000&key=AIzaSyBvoa_cVYNcrQn8SqwaaVb3bCI2rGqxrpQ`;
    console.log(ytShortsURL);
    const { data } = await axios.get(ytShortsURL, {
      method: "get",
    });
    console.log(data);
    setAllShorts(data.items);
  };

  useEffect(() => {
    getAllShorts();
  }, []);

  const sendUserToViewShorts = (videoId) => {
    const shortsData = [];
    for (let shorts of allShorts) {
      if (shorts.id.videoId !== videoId) {
        shortsData.push({ id: shorts.id.videoId });
      }
    }
    router.push({
      pathname: `/shortsviewer/${videoId}`,
    });
    sessionStorage.setItem("SHORTS_ID", JSON.stringify(shortsData));
  };

  return (
    <div className="w-full p-4 bg-white">
      <div className="flex items-end justify-between mb-2 header">
        <div className="title">
          <p className="text-4xl font-bold text-gray-800">Lastest Shorts</p>
        </div>
      </div>
      <div className="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
        {allShorts.map((item, key) => (
          <div
            className="overflow-hidden rounded-lg shadow-lg cursor-pointer h-90"
            key={item.id.videoId}
            onClick={() => sendUserToViewShorts(item.id.videoId)}
          >
            {/* <a href="#" className="block w-full h-full"> */}
            <img
              alt="blog photo"
              src={item.snippet.thumbnails.high.url}
              className="object-cover w-full max-h-40"
            />
            <div className="w-full p-4 bg-white dark:bg-gray-800">
              <p className="font-medium text-indigo-500 text-md">Short</p>
              <p className="mb-2 text-xl font-medium text-gray-800 dark:text-white">
                {item.snippet.title.slice(0, 30)}
              </p>
              <div className="flex items-center mt-4">
                {/* <a href="#" className="relative block"> */}
                <img
                  alt="profil"
                  src="/assets/images/dp_large.png"
                  className="object-cover rounded-full h-10 w-10 "
                />
                {/* </a> */}
                <div className="flex flex-col justify-between ml-2 text-sm">
                  <p className="text-gray-800 dark:text-white">
                    {item.snippet.channelTitle}
                  </p>
                  <p className="text-gray-400 dark:text-gray-300">
                    {item.snippet.publishedAt}
                  </p>
                </div>
              </div>
            </div>
            {/* </a> */}
          </div>
        ))}
      </div>
    </div>
  );
};

export default Home;
