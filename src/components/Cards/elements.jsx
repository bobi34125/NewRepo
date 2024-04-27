// elements.jsx
import styled from 'styled-components';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faFacebookF, faInstagram, faYoutube, faLinkedin } from '@fortawesome/free-brands-svg-icons';

export const Container = styled.div`
  display: flex;
  flex-direction: row;
  justify-content: center;
  align-items: center;
`;

export const VideoContainer = styled.div`
  width: 200px;
  height: auto;
  margin-right: 20px;
  border-radius: 10px;
  overflow: hidden;
  cursor: pointer;
`;

export const Video = styled.video`
  width: 100%;
  height: auto;
`;

export const CardContainer = styled.div`
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 20px;
`;

export const Card = styled.div`
  position: relative;
  width: 200px;
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
`;

export const Icon = styled.div`
  font-size: 48px;
  margin-bottom: 10px;
  color: ${({ color }) => color};
`;

export const Label = styled.div`
  font-size: 16px;
  text-align: center;
  position: absolute;
  top: 0;
  left: 50%;
  transform: translateX(-50%);
  width: 100%;
  font-size: 23px;
`;
