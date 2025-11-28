import { Link } from 'react-router-dom';
import './css/SongCard.css';


const SongCard = ({ song }) => {
  return (
    <div className="card-wrap">
      <div className="card">
      <Link to={`/player/${song.Id}`}>
          <img src={`IMG/${song.IMG}`}  alt="Cover"/>
          <div className="song-info">
            <p className="name-song">{song.NomeCanzone}</p>
            <p className="autor-song">{song.Autore}</p>
          </div>
    </Link>
      </div>
    </div>
  );
};

export default SongCard;