# Autobit
2021년도 종합 설계 과목 프로젝트입니다.  

자동 매매 트레이딩 전략을 검증해보고 공유하는 서비스입니다.  

<br/>

*Autobit - Frontend + Backend : [Autobit](https://github.com/Serpong/Autobit)*  
*Autobit - Python data server : [Autobit-python-server](https://github.com/Serpong/Autobit-python-server)*  

<br/>

## 👍 Autobit 요약
 - 트레이딩 전략을 사이트에서 바로 코딩해 볼 수 있습니다.
 - 과거의 가격 데이터를 이용해서 전략의 승률, 수익률 등을 실시간으로 검사해 볼 수 있습니다. (=**백테스팅**)  
 - 다른 유저들의 트레이딩 전략을 구경하거나 나의 전략을 공유할 수 있습니다.  
 - 실시간 코인 차트에서 코인의 매수, 매도 타이밍을 실시간으로 확인 할 수 있습니다.

<br/>

## ⏱ 개발 기간
2021.03 ~ 2021.06  

<br/>

## 📚 STACKS
### [Autobit](https://github.com/Serpong/Autobit)
<img src="https://img.shields.io/badge/html5-E34F26?style=for-the-badge&logo=html5&logoColor=white"> <img src="https://img.shields.io/badge/css-1572B6?style=for-the-badge&logo=css3&logoColor=white"> <img src="https://img.shields.io/badge/javascript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black"> <img src="https://img.shields.io/badge/jquery-0769AD?style=for-the-badge&logo=jquery&logoColor=white"> <img src="https://img.shields.io/badge/php-777BB4?style=for-the-badge&logo=php&logoColor=white"> <img src="https://img.shields.io/badge/mysql-4479A1?style=for-the-badge&logo=mysql&logoColor=white">  

### [Autobit-python-server](https://github.com/Serpong/Autobit-python-server)  
<img src="https://img.shields.io/badge/python-3776AB?style=for-the-badge&logo=python&logoColor=white"> <img src="https://img.shields.io/badge/mysql-4479A1?style=for-the-badge&logo=mysql&logoColor=white"> 


<br/>

## 🌅 PREVIEW

#### 메인페이지
![1](https://user-images.githubusercontent.com/9810848/171183533-f9123e7f-b06b-40d4-a209-fbd184e8f541.png)  

#### 전략 상세페이지
![2](https://user-images.githubusercontent.com/9810848/171183546-ad10da99-d842-4c48-9259-97b0947e0ab8.png)  

#### 전략 목록페이지
![3](https://user-images.githubusercontent.com/9810848/171183551-dda0f1b1-072e-412b-83c6-b490534e9f92.png)  

#### 전략 관리페이지 
![4](https://user-images.githubusercontent.com/9810848/171183553-934ef82e-3680-4c37-90b4-69db628dbfdd.png)  

#### 전략 수정페이지
![5](https://user-images.githubusercontent.com/9810848/171183558-5c297ba9-1063-4ec7-9fee-7e5d9a48d422.png)  


#### 💻 전략 코드 예시
    # 이전 가격보다 2배 이상 커지거나 작아지면 매수, 매도하는 전략 코드
    def make_strategy(prices):
    	if (prices[-1]/prices[-2]) > 2 :
    		return 'buy'
    	elif (prices[-2]/prices[-1]) > 2:
    		return 'sell'
      
<br/>  
<br/>  

## 📈 예시

![6](https://user-images.githubusercontent.com/9810848/171184941-d3cf3448-aa37-443b-b110-8112d62a2037.png)  
전략: `MACD`  
코인: `이더리움`  
기간: `2021.07.01 ~ 현재(2022.05.31)`  
단위: `1일`  

***수익 결과***  
바이앤홀드:  `0.53%` (처음 매수 후 매도하지 않았을 경우)  
해당 전략:  `34.83%`  

<br/>

## 📖 참고 문헌

 - 차트 API - [트레이딩 뷰](https://tradingview.com/)  
 - 가격 데이터 API - [업비트](https://docs.upbit.com/)

<!--## 🛠️ TOOLS-->
