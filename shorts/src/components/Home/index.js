import axios, { all } from "axios";
import React, { useEffect, useState } from "react";
import { useRouter } from "next/router";
import { API_ROOT_PATH } from "../../helper/helper";
import Image from "next/image";

const Home = () => {
  const [allShorts, setAllShorts] = useState([]);
  const [filterShorts, setFilterShorts] = useState(true);
  const router = useRouter();

  const getAllShorts = async () => {
    // const ytShortsURL = `https://www.googleapis.com/youtube/v3/search?videoDuration=short&channelId=UCg22-16kmYWZTUQF9wVkqFA&order=date&part=snippet&type=video&maxResults=1000&key=AIzaSyBvoa_cVYNcrQn8SqwaaVb3bCI2rGqxrpQ`;
    const ytShortsURL = `/api/getshortslist`;
    const { data } = await axios.get(ytShortsURL, {
      method: "get",
    });
    setAllShorts(data.records);
  };

  // const toggleFilterShorts = () => {
  //   if (filterShorts === true) {
  //     let finalShortsArr = [];
  //     for (let d of allShorts) {
  //       let isShorts = d.snippet.title.split(" ").includes("#shorts");
  //       if (isShorts === true) {
  //         finalShortsArr.push(d);
  //       }
  //     }
  //     setMappingArr(finalShortsArr);
  //   } else {
  //     setMappingArr(allShorts);
  //   }
  // };

  // const filteredShorts = allShorts.filter((item, key) => {
  //   return (
  //     item.snippet.title
  //       .toLowerCase()
  //       .indexOf(filterShorts === true ? "#shorts".toLowerCase() : "") !== -1
  //   );
  // });

  useEffect(() => {
    getAllShorts();
  }, []);

  const sendUserToViewShorts = (videoId) => {
    const shortsData = [];
    for (let shorts of allShorts) {
      if (shorts.VIDEO_ID !== videoId) {
        shortsData.push({ id: shorts.VIDEO_ID });
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
        <div className="flex justify-between title w-full">
          <p className="text-4xl font-bold text-gray-800">
            Lastest {filterShorts === true ? "Shorts" : "Videos"}
          </p>
          {/* <button
            onClick={() => {
              setFilterShorts(!filterShorts);
              toggleFilterShorts();
            }}
          >
            TOGGLE
          </button> */}
          {/* <div className="mb-3">
            <div className="relative inline-block w-10 mr-2 align-middle select-none">
              <input
                type="checkbox"
                name="toggle"
                id="Green"
                className="checked:bg-green-500 outline-none focus:outline-none right-4 checked:right-0 duration-200 ease-in absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer"
                onChange={() => {
                  setFilterShorts(!filterShorts);
                }}
              />
              <label
                htmlFor="Green"
                className="block h-6 overflow-hidden bg-gray-300 rounded-full cursor-pointer"
              ></label>
            </div>
            <span className="font-medium text-gray-400">
              {filterShorts === true ? "Shorts" : "Videos"}
            </span>
          </div> */}
        </div>
      </div>
      <div className="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
        {allShorts.map((item, key) => (
          <div
            className="overflow-hidden rounded-lg shadow-lg cursor-pointer h-90"
            key={item.VIDEO_ID}
            onClick={() => sendUserToViewShorts(item.VIDEO_ID)}
          >
            {/* <a href="#" className="block w-full h-full"> */}
            <Image
              alt="blog photo"
              src={item.VIDEO_THUMB_URL}
              className="object-cover w-full max-h-40"
            />
            <div className="w-full p-4 bg-white dark:bg-gray-800">
              <p className="font-medium text-indigo-500 text-md">Short</p>
              <p className="mb-2 text-xl font-medium text-gray-800 dark:text-white">
                {item.VIDEO_TITLE.slice(0, 30)}...
              </p>
              <div className="flex items-center mt-4">
                {/* <a href="#" className="relative block"> */}
                <Image
                  alt="profil"
                  src="/assets/images/dp_large.png"
                  className="object-cover rounded-full h-10 w-10 "
                />
                {/* </a> */}
                <div className="flex flex-col justify-between ml-2 text-sm">
                  <p className="text-gray-800 dark:text-white">
                    Delicious Pakwan
                  </p>
                  <p className="text-gray-400 dark:text-gray-300">
                    {item.VIDEO_PUBLISH_DATE}
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
