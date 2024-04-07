import sys
import argparse
import os
import numpy as np
import cv2 as cv
from sface import SFace
sys.path.append('../face_detection_yunet')
from yunet import YuNet
import pyvirtualcam

backend_target_pairs = [
    [cv.dnn.DNN_BACKEND_OPENCV, cv.dnn.DNN_TARGET_CPU],
    [cv.dnn.DNN_BACKEND_CUDA,   cv.dnn.DNN_TARGET_CUDA],
    [cv.dnn.DNN_BACKEND_CUDA,   cv.dnn.DNN_TARGET_CUDA_FP16],
    [cv.dnn.DNN_BACKEND_TIMVX,  cv.dnn.DNN_TARGET_NPU],
    [cv.dnn.DNN_BACKEND_CANN,   cv.dnn.DNN_TARGET_NPU]
]

backend_id = backend_target_pairs[1][0]
target_id = backend_target_pairs[1][1]

if __name__ == '__main__':
    recognizer = SFace(modelPath='face_recognition_sface_2021dec.onnx',
                       disType=0,
                       backendId=backend_id,
                       targetId=target_id)
    detector = YuNet(modelPath='../face_detection_yunet/face_detection_yunet_2023mar.onnx',
                     inputSize=[640, 640],
                     confThreshold=0.75,
                     nmsThreshold=0.3,
                     topK=5000,
                     backendId=backend_id,
                     targetId=target_id)


    folder_path = './authorized_faces/'
    faces = []
    for filename in os.listdir(folder_path):
        if filename.endswith(('.jpg', '.jpeg', '.png')):
            image_path = os.path.join(folder_path, filename)
            img1 = cv.imread(image_path)
            detector.setInputSize([img1.shape[1], img1.shape[0]])
            face1 = detector.infer(img1)
            assert face1.shape[0] > 0, 'Cannot find a face in {}'.format(args.input1)
            faces.append([img1, face1, filename])



cap = cv.VideoCapture(0)


def dvisualize(image, results, box_color=(0, 255, 0), text_color=(0, 0, 255), fps=None):
    output = image.copy()

    for det in results:
        bbox = det[0:4].astype(np.int32)
        cv.rectangle(output, (bbox[0], bbox[1]), (bbox[0]+bbox[2], bbox[1]+bbox[3]), (0, 0, 0), cv.FILLED)

    return output

def visualize(image, results, box_color=(0, 255, 0), text_color=(0, 0, 255), fps=None):
    output = image.copy()
    landmark_color = [
        (255,   0,   0), # right eye
        (  0,   0, 255), # left eye
        (  0, 255,   0), # nose tip
        (255,   0, 255), # right mouth corner
        (  0, 255, 255)  # left mouth corner
    ]

    if fps is not None:
        cv.putText(output, 'FPS: {:.2f}'.format(fps), (0, 15), cv.FONT_HERSHEY_SIMPLEX, 0.5, text_color)

    for det in results:
        bbox = det[0:4].astype(np.int32)
        cv.rectangle(output, (bbox[0], bbox[1]), (bbox[0]+bbox[2], bbox[1]+bbox[3]), box_color, 2)

        conf = det[-1]
        cv.putText(output, '{:.4f}'.format(conf), (bbox[0], bbox[1]+12), cv.FONT_HERSHEY_DUPLEX, 0.5, text_color)

        landmarks = det[4:14].astype(np.int32).reshape((5,2))
        for idx, landmark in enumerate(landmarks):
            cv.circle(output, landmark, 2, landmark_color[idx], 2)

    return output


w = int(cap.get(cv.CAP_PROP_FRAME_WIDTH))
h = int(cap.get(cv.CAP_PROP_FRAME_HEIGHT))
detector.setInputSize([w, h])

tm = cv.TickMeter()

width = int(cap.get(cv.CAP_PROP_FRAME_WIDTH))
height = int(cap.get(cv.CAP_PROP_FRAME_HEIGHT))
fps = cap.get(cv.CAP_PROP_FPS)


with pyvirtualcam.Camera(width, height, fps, fmt=pyvirtualcam.PixelFormat.BGR) as cam:

    print('Virtual camera device: ' + cam.device)
    while True:
        hasFrame, frame = cap.read()
        if not hasFrame:
            print('No frames grabbed!')
            break

        # Inference
        tm.start()
        detector.setInputSize([frame.shape[1], frame.shape[0]])
        results = detector.infer(frame) # results is a tuple
        assert frame.shape[0] > 0, 'Cannot find a face in frame'
        tm.stop()

        if len(results) >= 1:
            for i in results:
                f = False
                for j in faces:
                    result = recognizer.match(j[0], j[1][0][:-1], frame, i[:-1])
                    if result:
                        print("FACE MATCHED :", j[-1].split('.')[0])
                        f = True
                        break

                if not f:
                    frame = dvisualize(frame, [i], fps=tm.getFPS())
        cam.send(frame)
        cam.sleep_until_next_frame()
        tm.reset()
