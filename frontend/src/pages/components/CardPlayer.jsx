// eslint-disable-next-line no-unused-vars
import React from 'react';
import PropTypes from 'prop-types'; // Importa PropTypes
import Bottone from './Bottone'; 
import './css/CardPlayer.css';

const CardPlayer = ({ nomeCanzone, autore, img, controlloVideo }) => {
  return (
    <div className="box">
      <div className="coverCanzone">
        <img src={`/IMG/${img}`} alt={nomeCanzone} /> {/* Aggiungi un alt per accessibilità */}
      </div>

      <div className="nomeCanzonePlayer">
        <p>
          <strong>{nomeCanzone}</strong>
        </p>
      </div>

      <div className="nomeAutorePlayer">
        <p>{autore}</p>
      </div>

      <Bottone controlloVideo={controlloVideo} />
    </div>
  );
};

// Definisci la validazione delle props
CardPlayer.propTypes = {
  nomeCanzone: PropTypes.string.isRequired, // nomeCanzone è una stringa ed è obbligatoria
  autore: PropTypes.string.isRequired, // autore è una stringa ed è obbligatoria
  img: PropTypes.string.isRequired, // img è una stringa ed è obbligatoria
  controlloVideo: PropTypes.object.isRequired, // controlloVideo è un oggetto ed è obbligatorio
};

export default CardPlayer;