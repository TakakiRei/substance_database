// cross_section.cpp : このファイルには 'main' 関数が含まれています。プログラム実行の開始と終了がそこで行われます。
//

#include <GL/glut.h>
#include <iostream>
#include <fstream>
#include <string>
#include <array>
#include <vector>

#define width0 400
#define height0 360
#define ez0 210.0
#define h_inc 0.3
#define ez_inc 30.0

typedef std::array<GLdouble, 2> point2d;
typedef std::vector<point2d> plg;
typedef std::vector<plg> sec;
typedef std::vector<sec> allsec;

typedef std::array<GLdouble, 3> point3d;
typedef std::array<point3d, 3> tri;

bool Flag1 = 0, Flag2 = 0, drag_mouse_r = 0;
int last_x, last_y;
int width, height;
unsigned int last_max_index = 0, index = 1;
GLdouble diff_x, diff_y, ez, zoom_in;
GLdouble cut_z = 0.0;
allsec sections;

void filename_read(std::string fname, std::ifstream& ifs)
{
    std::cout << "ファイル名：";
    std::cin >> fname;
    ifs.open(fname);
}

int index_cal(GLdouble z)
{
    GLdouble rate1 = z / h_inc;
    int rate2 = (int)rate1;

    if (rate1 > (GLdouble)rate2) {
        return rate2 + 1;
    }

    return rate2;
}

point2d point_cal(point3d high_vertex, point3d low_vertex)
{
    GLdouble rate = (cut_z - low_vertex[2]) / (high_vertex[2] - low_vertex[2]);
    point2d p;

    p[0] = rate * (high_vertex[0] - low_vertex[0]) + low_vertex[0];
    p[1] = rate * (high_vertex[1] - low_vertex[1]) + low_vertex[1];

    return p;
}

bool nan_or_inf(point2d p) 
{
    return isnan(fabs(p[0])) || isinf(fabs(p[0])) || isnan(fabs(p[1])) || isinf(fabs(p[1]));
}

void add_index_def(plg& polygon, point2d p, bool flag)
{
    if (flag) {
        polygon.insert(polygon.begin(), p);
    }
    else {
        polygon.emplace_back(p);
    }
}

void elm_add_sub(sec& section, point2d p, int i, bool flag)
{
    int j, k;

    add_index_def(section[i], p, flag);

    for (j = i + 1; j < (int)section.size(); j++) {
        if (section[j].front() == p) {
            for (k = 1; k < (int)section[j].size(); k++) {
                add_index_def(section[i], section[j][k], flag);
            }
            section.erase(section.begin() + j);
            break;
        }
        if (section[j].back() == p) {
            for (k = (int)section[j].size() - 2; k >= 0; k--) {
                add_index_def(section[i], section[j][k], flag);
            }
            section.erase(section.begin() + j);
            break;
        }
    }

    Flag2 = 1;
}

void elm_add_main(sec& section, point2d p1, point2d p2)
{
    int i;
    plg polygon;

    for (i = 0; i < (int)section.size(); i++) {
        if (section[i].front() == p1) {
            elm_add_sub(section, p2, i, 1);
            break;
        }

        if (section[i].front() == p2) {
            elm_add_sub(section, p1, i, 1);
            break;
        }

        if (section[i].back() == p1) {
            elm_add_sub(section, p2, i, 0);
            break;
        }

        if (section[i].back() == p2) {
            elm_add_sub(section, p1, i, 0);
            break;
        }
    }

    if (Flag2 == 0) {
        section.emplace_back(polygon);
        section.back().reserve(1000);
        section.back().emplace_back(p1);
        section.back().emplace_back(p2);
    }

    Flag2 = 0;
    cut_z += h_inc;
}

void classification(tri triangle)
{
    unsigned int i, max_index, min_index, mid_index;

    point3d max_vertex = triangle[0], min_vertex = max_vertex, mid_vertex = max_vertex;
    point2d p1, p2;
    sec section;

    if (triangle[1][2] > max_vertex[2]) {
        max_vertex = triangle[1];
    }
    else {
        min_vertex = triangle[1];
    }

    if (triangle[2][2] > max_vertex[2]) {
        mid_vertex = max_vertex;
        max_vertex = triangle[2];
    }
    else if (triangle[2][2] < min_vertex[2]) {
        mid_vertex = min_vertex;
        min_vertex = triangle[2];
    }
    else {
        mid_vertex = triangle[2];
    }

    min_index = index_cal(min_vertex[2]);
    mid_index = index_cal(mid_vertex[2]);
    max_index = (int)(max_vertex[2] / h_inc) + 1;
    cut_z = h_inc * min_index;

    if (max_index > last_max_index) {
        for (i = last_max_index; i < max_index; i++) {
            sections.emplace_back(section);
            sections[i].reserve(50);
        }
        last_max_index = max_index;
    }

    for (i = min_index; i < mid_index; i++) {
        p1 = point_cal(max_vertex, min_vertex);
        if (nan_or_inf(p1)) {
            continue;
        }
        p2 = point_cal(mid_vertex, min_vertex);
        if (nan_or_inf(p2)) {
            continue;
        }
        elm_add_main(sections[i], p1, p2);
    }
    
    for (i = mid_index; i < max_index; i++) {
        p1 = point_cal(max_vertex, min_vertex);
        if (nan_or_inf(p1)) {
            continue;
        }
        p2 = point_cal(max_vertex, mid_vertex);
        if (nan_or_inf(p2)) {
            continue;
        }
        elm_add_main(sections[i], p1, p2);
    }
}

void data_read()
{
    unsigned int i = 0, j;
    const char* format = "         vertex %lf %lf %lf";
    GLdouble v[3];

    std::string fname;
    std::string str;
    std::ifstream ifs;
    
    tri triangle;

    sections.reserve(3000);

    filename_read(fname, ifs);
    while (ifs.fail()) {
        std::cout << "ファイルを開くことができませんでした。\n" << std::endl;
        filename_read(fname, ifs);
    }

    std::cout << "ファイルを読み込んでいます。" << std::endl;

    while (std::getline(ifs, str)) {
        if (str.find("vertex") != std::string::npos) {
            sscanf_s(str.c_str(), format, v, v + 1, v + 2);
            triangle[i][0] = v[0];
            triangle[i][1] = v[1];
            triangle[i][2] = v[2];
            i++;
            if (i == 3) {
                classification(triangle);
                i = 0;
            }
        }
    }
    
    sections.shrink_to_fit();
    for (i = 0; i < sections.size(); i++) {
        sections[i].shrink_to_fit();
        for (j = 0; j < sections[i].size(); j++) {
            sections[i][j].shrink_to_fit();
            if (sections[i][j].size() < 3) {
                sections[i].erase(sections[i].begin() + j);
                j--;
            }
        }
    }

    std::cout << "ファイルの読み込みが完了しました。\n\n" << std::endl;
    std::cout << "「←」キー：左への移動" << std::endl;
    std::cout << "「→」キー：右への移動" << std::endl;
    std::cout << "「↑」キー：上への移動" << std::endl;
    std::cout << "「↓」キー：下への移動" << std::endl;
    std::cout << "「i」キー：ズームイン" << std::endl;
    std::cout << "「o」キー：ズームアウト" << std::endl;
    std::cout << "「h」キー：切断面のz座標の増加" << std::endl;
    std::cout << "「l」キー：切断面のz座標の減少" << std::endl;
    std::cout << "「r」キー：カメラ位置とz座標のリセット" << std::endl;
    std::cout << "「q」キー：終了" << std::endl;
}

void eye_init()
{
    diff_x = 0.0;
    diff_y = 0.0;
    ez = ez0;
    zoom_in = 1.0;
}

void zoom_in_def(GLdouble diff_ez)
{
    ez += diff_ez;

    if (ez < ez_inc) {
        ez = ez_inc;
    }
    else if (ez > 3000) {
        ez = 3000;
    }

    zoom_in = ez0 / ez;
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
        glVertex2d(i, -range);
        glVertex2d(i, range);
    }
    for (i = itv; i < range; i += itv) {
        glVertex2d(i, -range);
        glVertex2d(i, range);
    }
    for (i = -range; i < 0.0; i += itv) {
        glVertex2d(-range, i);
        glVertex2d(range, i);
    }
    for (i = itv; i < range; i += itv) {
        glVertex2d(-range, i);
        glVertex2d(range, i);
    }
    glEnd();
    glDisable(GL_LINE_STIPPLE);

    glColor3d(0.0, 0.0, 0.0);
    glBegin(GL_LINES);
    glVertex2i(0, -range);
    glVertex2i(0, range);
    glVertex2i(-range, 0);
    glVertex2i(range, 0);
    glEnd();
}

GLdouble str_pos_x(GLdouble x)
{
    return x + ((double)width - width0) / 10;
}

GLdouble str_pos_y(GLdouble y)
{
    return y + ((double)height - height0) / 10;
}

void z_coordinate()
{
    unsigned int i;

    std::string str = std::to_string(h_inc * index);

    for (i = 0; i < str.size(); i++) {
        if (str[i] == '.') {
            str.replace(i + 2, str.size() - 1, "");
            break;
        }
    }

    while (str.size() != 5) {
        str.insert(0, " ");
    }

    str = "z = " + str;

    glColor4d(1.0, 1.0, 1.0, 0.0);
    glBegin(GL_POLYGON);
    glVertex2d(str_pos_x(11.0), str_pos_y(35.0));
    glVertex2d(str_pos_x(11.0), str_pos_y(28.0));
    glVertex2d(str_pos_x(39.0), str_pos_y(28.0));
    glVertex2d(str_pos_x(39.0), str_pos_y(35.0));
    glEnd();

    glColor3d(0.0, 0.0, 0.0);
    glBegin(GL_LINE_LOOP);
    glVertex2d(str_pos_x(11.0), str_pos_y(35.0));
    glVertex2d(str_pos_x(11.0), str_pos_y(28.0));
    glVertex2d(str_pos_x(39.0), str_pos_y(28.0));
    glVertex2d(str_pos_x(39.0), str_pos_y(35.0));
    glEnd();

    glRasterPos2d(str_pos_x(12.0), str_pos_y(30.0));
    for (i = 0; i < str.size(); i++) {
        glutBitmapCharacter(GLUT_BITMAP_TIMES_ROMAN_24, str[i]);
    }
    glRasterPos2d(str_pos_x(30.0), str_pos_y(30.0));
    glutBitmapCharacter(GLUT_BITMAP_TIMES_ROMAN_24, 'm');
    glutBitmapCharacter(GLUT_BITMAP_TIMES_ROMAN_24, 'm');
}

void display()
{
    unsigned int i, j;
    GLdouble v[2];

    glClear(GL_COLOR_BUFFER_BIT | GL_DEPTH_BUFFER_BIT);

    glLoadIdentity();

    glTranslated(-diff_x * zoom_in, -diff_y * zoom_in, 0);

    grid(10.0 * zoom_in, (int)(3000 * zoom_in));
    
    for (i = 0; i < sections[index].size(); i++) {
        glColor3d(0.4, 0.4, 0.4);
        glBegin(GL_POLYGON);
        for (j = 0; j < sections[index][i].size(); j++) {
            v[0] = sections[index][i][j][0] * zoom_in;
            v[1] = sections[index][i][j][1] * zoom_in;
            glVertex2dv(v);
        }
        glEnd();

        glColor3d(0.0, 0.0, 0.0);
        glBegin(GL_LINE_LOOP);
        for (j = 0; j < sections[index][i].size(); j++) {
            v[0] = sections[index][i][j][0] * zoom_in;
            v[1] = sections[index][i][j][1] * zoom_in;
            glVertex2dv(v);
        }
        glEnd();
    }

    glTranslated(diff_x * zoom_in, diff_y * zoom_in, 0);

    z_coordinate();

    glutSwapBuffers();
}

void resize(int w, int h)
{
    glViewport(0, 0, w, h);

    glMatrixMode(GL_PROJECTION);
    glLoadIdentity();
    glOrtho(-w / 10.0, w / 10.0, -h / 10.0, h / 10.0, -ez0, 1.0);

    glMatrixMode(GL_MODELVIEW);

    width = w;
    height = h;
}

void keyboard(unsigned char key, int x, int y)
{
    switch (key) {
    case 'o':
        zoom_in_def(ez_inc);
        break;
    case 'i':
        zoom_in_def(-ez_inc);
        break;
    case 'h':
        index++;
        if (index >= last_max_index) {
            index = 0;
        }
        break;
    case 'l':
        index--;
        if ((int)index < 0) {
            index = last_max_index - 1;
        }
        break;
    case 'r':
        eye_init();
        index = 1;
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
        diff_x -= 10.0;
        break;
    case GLUT_KEY_RIGHT:
        diff_x += 10.0;
        break;
    case GLUT_KEY_UP:
        diff_y += 10.0;
        break;
    case GLUT_KEY_DOWN:
        diff_y -= 10.0;
        break;
    default:
        break;
    }
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
    if (drag_mouse_r == 1)
    {
        diff_x -= ((GLdouble)x - (GLdouble)last_x) * ez / ez0 / 6;
        diff_y += ((GLdouble)y - (GLdouble)last_y) * ez / ez0 / 6;
    }

    last_x = x;
    last_y = y;
}

void idle()
{
    glutPostRedisplay();
}

void init()
{
    glClearColor(1.0, 1.0, 1.0, 1.0);
}

int main(int argc, char* argv[])
{
    if (Flag1 == 0) {
        data_read();
        eye_init();
        Flag1 = 1;
    }

    glutInitWindowSize(width0, height0);
    glutInit(&argc, argv);
    glutInitDisplayMode(GLUT_RGBA | GLUT_DOUBLE);
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
