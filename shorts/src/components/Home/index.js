import axios from "axios";
import React, { useEffect, useState } from "react";
import { useRouter } from "next/router";
import moment from "moment";
import Image from "next/image";
import Spinner from "../utils/Spinner";
import Pagination from "../Pagination";

const Home = () => {
  const [allShorts, setAllShorts] = useState([]);
  const [loading, setLoading] = useState(true);
  const [currentPageNum, setCurrentPageNum] = useState(1);
  const router = useRouter();

  const getAllShorts = async () => {
    const ytShortsURL = `/api/getshortslist`;
    const { data } = await axios.get(ytShortsURL, {
      method: "get",
    });
    setAllShorts(data.records);
    setLoading(false);
  };

  useEffect(() => {
    if (sessionStorage.getItem("currPageNum") !== null) {
      setCurrentPageNum(parseInt(sessionStorage.getItem("currPageNum")));
    }
    getAllShorts();
  }, []);

  const recPerPage = 7;
  const totalNumPages = Math.ceil(allShorts.length / recPerPage);
  const displayPageNumbers = [...Array(totalNumPages + 1).keys()].slice(1);
  const endPageIndex = currentPageNum * recPerPage;
  const startPageIndex = endPageIndex - recPerPage;

  const visibleShortsList = allShorts.filter((item, key) => {
    return key >= startPageIndex && key <= endPageIndex;
  });

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

  if (!loading) {
    return (
      <div className="w-full p-2">
        <div className="grid grid-cols-1 gap-2 md:grid-cols-2 xl:grid-cols-4 mt-2">
          {visibleShortsList.map((item, key) => (
            <div
              className="overflow-hidden rounded-lg shadow-lg cursor-pointer"
              style={{ minHeight: "295px" }}
              key={item.VIDEO_ID}
              onClick={() => sendUserToViewShorts(item.VIDEO_ID)}
            >
              {/* <a href="#" className="block w-full h-full"> */}
              <Image
                alt="blog photo"
                src={item.VIDEO_THUMB_URL}
                width="0"
                height="0"
                sizes="100vw"
                className="object-cover w-full h-auto"
                loading="eager"
                priority={true}
              />
              <div className="h-full p-2 bg-white dark:bg-gray-800">
                <div
                  style={{
                    height: "50px",
                    wordBreak: "break-all",
                    whiteSpace: "pre-wrap",
                  }}
                >
                  <p className="text-xs font-medium text-white">
                    {item.VIDEO_TITLE}
                  </p>
                </div>

                <div className="flex items-center">
                  <Image
                    alt="profil"
                    src="/assets/images/dp_large.png"
                    className="object-cover rounded-full h-10 w-10"
                    width="0"
                    height="0"
                    sizes="100vw"
                    loading="eager"
                    priority={true}
                  />
                  <div className="flex flex-col justify-between ml-2 text-xs">
                    <p className="text-gray-800 dark:text-white">
                      Delicious Pakwan
                    </p>
                    <p className="text-gray-400 dark:text-gray-300">
                      {moment(item.VIDEO_PUBLISH_DATE).format("MMMM D, YYYY")}
                    </p>
                  </div>
                </div>
              </div>
            </div>
          ))}
        </div>

        <Pagination
          displayPageNumbers={displayPageNumbers}
          currentPageNum={currentPageNum}
          setCurrentPageNum={setCurrentPageNum}
          totalNumPages={totalNumPages}
        />
      </div>
    );
  } else {
    return <Spinner />;
  }
};

export default Home;
