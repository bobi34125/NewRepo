import React, { useRef } from 'react';
import styled from 'styled-components';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faFacebookF, faInstagram, faYoutube, faLinkedin } from '@fortawesome/free-brands-svg-icons';

const Container = styled.div`
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
`;

const VideoContainer = styled.div`
  width: 100%;
  max-width: 300px;
  margin-bottom: 20px;
  border-radius: 10px;
  overflow: hidden;
  cursor: pointer;
`;

const Video = styled.video`
  width: 100%;
  height: auto;
`;

const CardContainer = styled.div`
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 20px;
  width: 100%;
  max-width: 300px;
`;

const Card = styled.a`
  position: relative;
  width: 100%;
  height: auto;
  border: 1px solid #ccc;
  border-radius: 10px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  padding: 20px;
  background-color: ${({ color }) => color};
  cursor: pointer;
  transition: transform 0.5s, background-color 0.5s;
  transform-style: preserve-3d;
  backface-visibility: hidden;
  text-decoration: none; /* Remove default link underline */
  color: inherit; /* Inherit text color */

  &:hover {
    transform: translateY(-5px); /* Add a slight hover effect */
  }
`;

const Icon = styled.div`
  font-size: 48px;
  margin-bottom: 10px;
  color: ${({ color }) => color};
`;

const Label = styled.div`
  font-size: 16px;
  text-align: center;
  position: absolute;
  top: 0;
  left: 50%;
  transform: translateX(-50%);
  width: 100%;
  font-size: 23px;
`;

const Cards = () => {
  const videoRef = useRef(null);

  const handleVideoClick = () => {
    const video = videoRef.current;
    if (video.paused) {
      video.play();
    } else {
      video.pause();
    }
  };

  return (
    <Container>
      <CardContainer>
        <Card href="https://www.facebook.com" target="_blank" rel="noopener noreferrer" color="#3b5998">
          <Icon color="#fff"><FontAwesomeIcon icon={faFacebookF} /></Icon>
          <Label>Facebook</Label>
        </Card>
        <Card href="https://www.instagram.com" target="_blank" rel="noopener noreferrer" color="#e4405f">
          <Icon color="#fff"><FontAwesomeIcon icon={faInstagram} /></Icon>
          <Label>Instagram</Label>
        </Card>
        <Card href="https://www.youtube.com" target="_blank" rel="noopener noreferrer" color="#cd201f">
          <Icon color="#fff"><FontAwesomeIcon icon={faYoutube} /></Icon>
          <Label>YouTube</Label>
        </Card>
        <Card href="https://www.linkedin.com" target="_blank" rel="noopener noreferrer" color="#0077b5">
          <Icon color="#fff"><FontAwesomeIcon icon={faLinkedin} /></Icon>
          <Label>LinkedIn</Label>
        </Card>
      </CardContainer>
    </Container>
  );
};

export default Cards;
