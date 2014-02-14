<div>
<blockquote>
	<h1>Lavid Online Judge FAQ</h1>
	<small> 자주 묻는 질문</small>
</blockquote>
<hr>
<div>
	<h4>Q. 채점을 위해 사용되는 컴파일러와 각 컴파일러의 옵션은 어떤것입니까?</h4>
	<h4>A. </h4>
	<div class="well well-large">
		</p>채점 시스템은 <a href="http://www.ubuntu.com/">Ubuntu Linux</a>에서 구동됩니다. 현재 C/C++ 코드 컴파일을 위해 <a href="http://gcc.gnu.org/">GNU GCC/G++</a>를 사용하고 있으며, 그리고 Java 코드 컴파일을 위해 <a href="http://www.gnu-pascal.de/gpc/h-index.html">sun-java-jdk1.6</a>를 사용합니다. 컴파일을 위해서 다음과 같은 옵션을 사용합니다</p>
		<br/><br/>
		<table class="table">
			<tr> 
				<td>C</td>
				<td>gcc Main.c -o Main -ansi -fno-asm -O2 -Wall -lm --static</td>
			</tr>
			<tr>
				<td>C++</td>
				<td>g++ Main.c -o Main -ansi -fno-asm -O2 -Wall -lm --static</td>
			</tr>
			<tr>
        <td>JAVA</td>
        <td>javac Main.java</td>
      </tr>
		</table>
		<br/><br/>
    <p> 현재 사용하고 있는 컴파일러 버전 </p>
		<a href="#">gcc/g++ (Ubuntu/Linaro 4.4.4-14ubuntu5.1) 4.4.5</a></br/>
		<a href="#">glibc 2.3.6</a><br/>
		<a href="#">Java 1.6.0_06</a><br/>
	</div>
</div>
<hr>
<div>
	<h4> Q. 입력과 출력은 어떻게 받나요? </h4>
	<h4> A. </h4>
	<div class="well well-large">
		채점을 위해서 입력은 stdin('Standard Input')을 통해 받게 되며, stdout('Standard Output')에 출력하게 됩니다. 자세하게 이야기 하자면, 입력을 위해서는 'scanf(C)/cin(C++)'을, 출력을 위해서 'printf(C)/cout(C++)' 을 사용하게 됩니다
사용자가 작성한 프로그램은 파일을 읽고 쓰는 것이 금지 되어 있으며, 이러한 경우, "Runtime Error" 를 받게 됩니다.
		아래는 세개의 코트는 1000번의 예제 코드입니다.  C, C++, JAVA 순입니다.
	</div>
	<h6>C 예제</h6>
	<div class="well well-small">
	#include <stdio.h><br/>
	int main(){<br/>
  &nbsp;&nbsp;&nbsp;&nbsp;int a,b;<br/>
	&nbsp;&nbsp;&nbsp;&nbsp;while(scanf("%d %d",&ampa, &ampb) != EOF)<br/>
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;printf("%d\n",a+b);<br/>
	&nbsp;&nbsp;&nbsp;&nbsp;return 0;<br/>
  }<br/>
	</div>
	<h6>C++ 예제</h6>
	<div class="well well-small">
	include <iostream><br/>
	using namespace std;<br/>
	int main(){<br/>
	&nbsp;&nbsp;&nbsp;&nbsp;int a,b;<br/>
	&nbsp;&nbsp;&nbsp;&nbsp;while(cin &gt&gt a &gt&gt b)<br/>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;cout &lt&lt a+b &lt&lt endl;<br/>
	&nbsp;&nbsp;&nbsp;&nbsp;return 0;<br/>
	}<br/>
	</div>
	<h6> JAVA 예제 </h6>
	<div class="well well-small">
	import java.util.*;<br/>
	public class Main{<br/>
	&nbsp;&nbsp;&nbsp;&nbsp;public static void main(String args[]){<br/>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Scanner cin = new Scanner(System.in);<br/>
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;int a, b;<br/>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;while (cin.hasNext()){<br/>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;a = cin.nextInt(), b = cin.nextInt();<br/>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;System.out.println(a + b);<br/>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}<br/>
	&nbsp;&nbsp;&nbsp;&nbsp;}<br/>
	}<br/>
	</div>
</div>
<hr/>
<div>
	<h4>Q. 제가 테스트 해보았을 떄는 잘 돌아가는데 결과로 Compile Error가 뜹니다. 왜그럴까요?</h4>
	<h4>A.</h4>
	<div class="well well-large">
	일반적으로 MS-VC++를 사용하였을 경우에 발생하며, GNU와 MS-VC++에서 생기는 차이점에 의하여 발생합니다. 예를 들면 다음과 같습니다<br/><br/>
	<ul>
		<li>G++에선 main이 반드시 int형으로 선언되어야 하며, void main을 사용하게 되면 Compile Error를 받게 됩니다.</li>
		<li>for(int i=0...){...}"와 같이 for문 안에 int변수를 선언하였을 경우 block을 벗어났을 때 i 변수는 사라지게 됩니다.</li>
		<li>itoa 는 ANSI 에서 규정한 표준 함수가 아닙니다.</li>
		<li>VC에서의 __int64는 ANSI 표준이 아닙니다. 하지만 64비트 integer 변수를 사용하기 위해 long long을 사용할 수 있습니다.</li>
	</ul>
	</div>
</div>
<hr>
<div>
	<h4>Q.채점 결과의 뜻은 무엇인가요?</h4>
	<h4>A.</h4>
	<div class="well well-large">
	채점의 결과는 아래와 같습니다.<br/><br/>
<font color="gray">Pending</font> : 채점이 밀려서 아직 채점이 완료 되지 않은 대기 상태. 일반적으로 1분 이내에 채점이 됩니다.<br/>
<font color="gray">Pending Rejudge</font> : 테스트 데이터를 새로이 고치거나 했을 경우, 해당 제출 코드를 다시 채점하게 되어 대기 상태로 들어가는 경우.<br/>
<font color="orange">Compiling</font>  : 채점을 하기 위해 컴파일 하는 중에 나타납니다.<br/>
<font color="orange">Running & Judging</font> : 채점이 진행되고 있음을 의미합니다.<br/>
<font color="green">Accepted</font>  : 제출한 프로그램이 모든 테스트 데이터를 통과했음을 뜻합니다.<br/>
<font color="red">Presentation Error</font>  : 출력 결과가 테스트 데이터와 유사하나, 공백, 빈 줄과 같은 사소한 문제로 인해 출력 결과가 일치하지 않는 경우입니다.<br/>
<font color="red">Wrong Answer</font>  : 출력 결과가 테스트 데이터와 다른 경우 입니다.<br/>
<font color="red">Time Limit Exceeded</font>  : 제출한 프로그램이 제한된 시간이내에 끝나지 않은 경우를 뜻합니다.<br/>
<font color="red">Memory Limit Exceeded</font>  : 제출한 프로그램이 허용된 메모리보다 많은 메모리를 사용했을 경우를 뜻합니다. <br/>
<font color="red">Output Limit Exceeded</font> : 예상하는 보다 많은 출력이 발생한 경우 입니다. 일반적으로 프로그램이 무한 루프에 빠졌을 경우에 일어납니다. 현재 채점 시스템에서 출력 제한은 1메가 바이트로 제한됩니다.<br/>
<font color="red">Runtime Error</font>  : 실행 도중에 'segmentation fault','floating point exception','used forbidden functions', 'tried to access forbidden memories' 등의 에러가 발생하여서 실행도중에 프로그램이 종료된 경우 입니다.<br/>
<font color="red">Compile Error</font>  : 컴파일러가 제출한 소스코드를 컴파일 하지 못한 경우입니다. 물론 경고 메시지(warning message)는 에러 메시지로 간주하지 않습니다. 채점 결과를 클릭하면 실제 에러 메시지를 볼 수 있습니다.<br/>

	</div>
</div>
</div>
