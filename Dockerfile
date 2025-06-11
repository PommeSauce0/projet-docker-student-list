FROM python:3.8-buster

LABEL maintainer="Louis <louismorvan3@gmail.com>"

WORKDIR /

COPY student_age.py .
COPY requirement.txt .

RUN apt-get update && apt-get install -y\
    python3-dev \
    libsasl2-dev \
    libldap2-dev \
    libssl-dev

RUN pip3 install -r requirement.txt

VOLUME /data
EXPOSE 5000
CMD ["python3", "student_age.py"]