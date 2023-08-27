// STL_read.cpp : このファイルには 'main' 関数が含まれています。プログラム実行の開始と終了がそこで行われます。
//

#define _USE_MATH_DEFINES
#include <GL/glut.h>
#include <iostream>
#include <fstream>
#include <string>
#include <array>
#include <vector>

#define deg10 0.0
#define deg20 30.0
#define deg_inc 10.0
#define r0 300.0

bool Flag1 = 0, drag_mouse_r = 0;
int last_x, last_y;
unsigned int count = 0;
GLdouble a[3];
GLdouble deg1, deg2;
GLdouble r = r0;
GLfloat light0pos[4];

std::vector<std::array<GLdouble, 3>> norvec;
std::vector<std::array<std::array<GLdouble, 3>, 3>> tri;

void deg1_increase(GLdouble inc)
{
    deg1 += inc;

    if (deg1 > 180) {
        deg1 -= -360;
    }
    else if (deg1 <= -180) {
        deg1 += 360;
    }
}

void deg2_increase(GLdouble inc)
{
    deg2 += inc;

    if (deg2 >= 90) {
        deg2 = 89.999999;
    }
    else if (deg2 <= -90) {
        deg2 = -89.999999;
    }
}

GLdouble deg_to_rad(GLdouble deg)
{
    return deg * M_PI / 180;
}

void a_init()
{
    GLdouble rad1, rad2;

    deg1 = deg10;
    deg2 = deg20;

    rad1 = deg_to_rad(deg1);
    rad2 = deg_to_rad(deg2);

    a[0] = sin(rad1);
    a[1] = -cos(rad2);
    a[2] = sin(rad2);
}

void a_update()
{
    GLdouble w, rad1 = deg_to_rad(deg1), rad2 = deg_to_rad(deg2);

    a[2] = sin(rad2);
    w = sqrt(1 - a[2] * a[2]);
    a[0] = -w * sin(rad1);
    a[1] = -w * cos(rad1);
}

void light0pos_update()
{
    GLdouble rad1 = deg_to_rad(deg1);

    light0pos[0] = (GLfloat)(r * a[0] - r * cos(rad1) / 6);
    light0pos[1] = (GLfloat)(r * a[1] + r * sin(rad1) / 6);
    light0pos[2] = (GLfloat)(r * a[2]);
}

void data_read()
{
    unsigned int i = 0;
    char fname1[100];
    const char* format1 = "   facet normal %lf %lf %lf";
    const char* format2 = "         vertex %lf %lf %lf";
    GLdouble v[3];

    std::string fname2;
    std::string str;
    std::ifstream ifs;
    std::array<GLdouble, 3> elm1;
    std::array<std::array<GLdouble, 3>, 3> elm2;

    std::cout << "ファイル名：";
    scanf_s("%s", fname1, 100);
    fname2 = fname1;
    ifs.open(fname2);

    while (ifs.fail()) {
        std::cout << "ファイルを開くことができませんでした。" << std::endl;
        std::cout << std::endl;
        std::cout << "ファイル名：";
        scanf_s("%s", fname1, 100);
        fname2 = fname1;
        ifs.open(fname2);
    }

    std::cout << "ファイルを読み込んでいます。" << std::endl;

    while (std::getline(ifs, str)) {
        if (str.find("facet normal") != std::string::npos) {
            norvec.emplace_back(elm1);
            sscanf_s(str.c_str(), format1, v, v + 1, v + 2);
            norvec[count][0] = v[0];
            norvec[count][1] = v[1];
            norvec[count][2] = v[2];
        }
        if (str.find("vertex") != std::string::npos) {
            sscanf_s(str.c_str(), format2, v, v + 1, v + 2);
            tri.emplace_back(elm2);
            tri[count][i][0] = v[0];
            tri[count][i][1] = v[1];
            tri[count][i][2] = v[2];
            i++;
            if (i == 3) {
                i = 0;
                count++;
            }
        }
    }
    
    std::cout << "ファイルの読み込みが完了しました。" << std::endl;
    std::cout << std::endl;
    std::cout << std::endl;
    std::cout << "「←」キー：左に10°の回転" << std::endl;
    std::cout << "「→」キー：右に10°の回転" << std::endl;
    std::cout << "「↑」キー：上に10°の回転" << std::endl;
    std::cout << "「↓」キー：下に10°の回転" << std::endl;
    std::cout << "「i」キー：ズームイン" << std::endl;
    std::cout << "「o」キー：ズームアウト" << std::endl;
    std::cout << "「r」キー：カメラ位置のリセット" << std::endl;
    std::cout << "「q」キー：終了" << std::endl;
}

void grid(GLdouble itv, int range)
{
    GLdouble i;

    glLineWidth(1);

    glEnable(GL_LINE_STIPPLE);
    glLineStipple(1, 0xF0F0);
    glColor3d(0.5, 0.5, 0.5);
    glBegin(GL_LINES);
    for (i = -range; i < 0.0; i += itv) {
        glVertex3d(i, -range, 0);
        glVertex3d(i, range, 0);
    }
    for (i = itv; i < range; i += itv) {
        glVertex3d(i, -range, 0);
        glVertex3d(i, range, 0);
    }
    for (i = -range; i < 0.0; i += itv) {
        glVertex3d(-range, i, 0);
        glVertex3d(range, i, 0);
    }
    for (i = itv; i < range; i += itv) {
        glVertex3d(-range, i, 0);
        glVertex3d(range, i, 0);
    }
    glEnd();
    glDisable(GL_LINE_STIPPLE);

    glColor3d(0.0, 0.0, 0.0);
    glBegin(GL_LINES);
    glVertex3i(0, -range, 0);
    glVertex3i(0, range, 0);
    glVertex3i(-range, 0, 0);
    glVertex3i(range, 0, 0);
    glEnd();
}

void display()
{
    unsigned int i;

    glClear(GL_COLOR_BUFFER_BIT | GL_DEPTH_BUFFER_BIT);

    glLoadIdentity();
    gluLookAt(r * a[0], r * a[1], r * a[2], 0.0, 0.0, 0.0, 0.0, 0.0, 1.0);

    light0pos_update();
    glLightfv(GL_LIGHT0, GL_POSITION, light0pos);

    grid(10, 10000);

    glBegin(GL_TRIANGLES);
    for (i = 0; i < count; i++) {
        glNormal3d(norvec[i][0], norvec[i][1], norvec[i][2]);
        glVertex3d(tri[i][0][0], tri[i][0][1], tri[i][0][2]);
        glVertex3d(tri[i][1][0], tri[i][1][1], tri[i][1][2]);
        glVertex3d(tri[i][2][0], tri[i][2][1], tri[i][2][2]);
    }
    glEnd();

    glutSwapBuffers();
}

void resize(int w, int h)
{
    glViewport(0, 0, w, h);

    glMatrixMode(GL_PROJECTION);
    glLoadIdentity();
    gluPerspective(30.0, (double)w / (double)h, 1.0, 3000.0);

    glMatrixMode(GL_MODELVIEW);
}

void keyboard(unsigned char key, int x, int y)
{
    switch (key) {
    case 'o':
        r += 30.0;
        break;
    case 'i':
        r -= 30.0;
        if (r < 0) {
            r = 0;
        }
        break;
    case 'r':
        a_init();
        r = r0;
        break;
    case 'q':
        exit(0);
        break;
    default:
        break;
    }
}

void special_key(int key, int x, int y)
{
    switch (key) {
    case GLUT_KEY_LEFT:
        deg1_increase(deg_inc);
        break;
    case GLUT_KEY_RIGHT:
        deg1_increase(-deg_inc);
        break;
    case GLUT_KEY_UP:
        deg2_increase(deg_inc);
        break;
    case GLUT_KEY_DOWN:
        deg2_increase(-deg_inc);
        break;
    default:
        break;
    }

    a_update();
}

void mouse(int button, int state, int x, int y)
{
    if ((button == GLUT_LEFT_BUTTON) && (state == GLUT_DOWN)) {
        drag_mouse_r = 1;
    }
    else if ((button == GLUT_LEFT_BUTTON) && (state == GLUT_UP)) {
        drag_mouse_r = 0;
    }

    last_x = x;
    last_y = y;
}

void motion(int x, int y)
{
    GLdouble diff_x, diff_y;

    if (drag_mouse_r == 1)
    {
        diff_x = (GLdouble)(x - (GLdouble)last_x) / 2;
        diff_y = (GLdouble)(y - (GLdouble)last_y) / 2;

        deg1_increase(diff_x);
        deg2_increase(diff_y);
    }

    last_x = x;
    last_y = y;

    a_update();
}

void idle()
{
    glutPostRedisplay();
}

void init()
{
    glClearColor(1.0, 1.0, 1.0, 1.0);

    glEnable(GL_DEPTH_TEST);

    glEnable(GL_CULL_FACE);
    glCullFace(GL_BACK);

    glEnable(GL_LIGHTING);
    glEnable(GL_LIGHT0);
}

int main(int argc, char* argv[])
{
    if (Flag1 == 0) {
        data_read();
        a_init();
        light0pos_update();
        light0pos[3] = 1.0;
        Flag1 = 1;
    }

    glutInitWindowSize(400, 360);
    glutInit(&argc, argv);
    glutInitDisplayMode(GLUT_RGBA | GLUT_DOUBLE | GLUT_DEPTH);
    glutCreateWindow(argv[0]);
    glutDisplayFunc(display);
    glutReshapeFunc(resize);
    glutKeyboardFunc(keyboard);
    glutSpecialFunc(special_key);
    glutMouseFunc(mouse);
    glutMotionFunc(motion);
    glutIdleFunc(idle);
    init();
    glutMainLoop();
    return 0;
}

// プログラムの実行: Ctrl + F5 または [デバッグ] > [デバッグなしで開始] メニュー
// プログラムのデバッグ: F5 または [デバッグ] > [デバッグの開始] メニュー

// 作業を開始するためのヒント: 
//    1. ソリューション エクスプローラー ウィンドウを使用してファイルを追加/管理します 
//   2. チーム エクスプローラー ウィンドウを使用してソース管理に接続します
//   3. 出力ウィンドウを使用して、ビルド出力とその他のメッセージを表示します
//   4. エラー一覧ウィンドウを使用してエラーを表示します
//   5. [プロジェクト] > [新しい項目の追加] と移動して新しいコード ファイルを作成するか、[プロジェクト] > [既存の項目の追加] と移動して既存のコード ファイルをプロジェクトに追加します
//   6. 後ほどこのプロジェクトを再び開く場合、[ファイル] > [開く] > [プロジェクト] と移動して .sln ファイルを選択します
