import React, { useState, useCallback } from 'react';
import axios from 'axios';
import { FaSearch } from 'react-icons/fa';
import './css/BarraDiRicerca.css';

// Hook personalizzato per la gestione della ricerca
const useSearch = () => {
    const [searchResults, setSearchResults] = useState([]);

    const handleSearchInput = useCallback(async (inputVal) => {
        if (inputVal.length) {
            try {
                const response = await axios.get("http://localhost:3002/api/search", {
                    params: { term: inputVal }
                });
                setSearchResults(response.data);
            } catch (error) {
                console.error("Errore durante la ricerca:", error);
                setSearchResults([]);
            }
        } else {
            setSearchResults([]);
        }
    }, []);

    return { searchResults, handleSearchInput };
};

const BarraDiRicerca = () => {
    const [isBoxVisible, setIsBoxVisible] = useState(false);
    const { searchResults, handleSearchInput } = useSearch();

    const toggleSearchBox = () => {
        setIsBoxVisible(!isBoxVisible);
    };

    const onInputChange = (e) => {
        const inputVal = e.target.value;
        handleSearchInput(inputVal);
    };

    return (
        <div className="search-container">
            {/* Barra di ricerca */}
            <div id="box" className={`search-box ${isBoxVisible ? 'visible' : 'hidden'}`}>
                <input 
                    type="text" 
                    autoComplete="off" 
                    placeholder="Cerca la tua canzone..." 
                    onKeyUp={onInputChange}
                    aria-label="Cerca la tua canzone"
                />
                {/* Risultati della ricerca */}
                <div className="result">
                    {searchResults.map((song, index) => (
                        <a key={song.id || index} href={`/Player/${song.Id}`} className="song-link">
                            <div className="song-item">
                                <img src={`IMG/${song.IMG}`} alt="Cover" className="song-cover" />
                                <div className="song-details">
                                    <strong>{song.NomeCanzone}</strong>
                                    <span>{song.Autore}</span>
                                </div>
                            </div>
                        </a>
                    ))}
                </div>
            </div>

            {/* Pulsante di ricerca */}
            <button id="ricerca" title="Barra Di Ricerca" onClick={toggleSearchBox}>
                <FaSearch size={30} color="rgb(255 255 255)" />
            </button>
        </div>
    );
};

export default React.memo(BarraDiRicerca);